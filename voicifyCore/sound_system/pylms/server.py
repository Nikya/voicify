"""
PyLMS: Python Wrapper for Logitech Media Server CLI
(Telnet) Interface

Copyright (C) 2010 JingleManSweep <jinglemansweep [at] gmail [dot] com>

Forked for use as a listening Client by Ben Weiner, https://github.com/readingtype

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
"""

import telnetlib
import urllib
import pylms
from pylms.player import Player
import json


class Server(object):

    """
    Server
    """

    def __init__(
            self,
            hostname="localhost",
            port=9090,
            username="",
            password="",
            charset="utf8"):

        """
        Constructor
        """
        self.debug = False
        self.logger = None
        self.telnet = None
        self.logged_in = False
        self.hostname = hostname
        self.port = port
        self.username = username
        self.password = password
        self.version = ""
        self.player_count = 0
        self.players = []
        self.charset = charset

    def connect(self, update=True):
        """
        Connect
        """
        self.telnet_connect()
        self.login()
        self.get_players(update=update)

    def disconnect(self):
        self.telnet.close()

    def telnet_connect(self):
        """
        Telnet Connect
        """
        self.telnet = telnetlib.Telnet(self.hostname, self.port)

    def login(self):
        """
        Login
        """
        result = self.request("login %s %s" % (self.username, self.password))
        self.logged_in = (result == "******")

    def request(self, command_string="", received=None, preserve_encoding=False):
        """
        Request
        """

        # self.logger.debug("Telnet: %s" % (command_string))
        if not received or received is None:
            self.telnet.write(pylms.encode(command_string + "\n", self.charset))
            response = self.telnet.read_until(pylms.encode("\n", self.charset))[:-1]
            #print "Sent command string [%s]" % command_string

        else:
            response = received

        #print "LMS Response: [%s]" % response

        start = command_string.split(" ")[0]

        if not preserve_encoding:
            response = self.decode_response(response)
            command_string = command_string

        if preserve_encoding:
            response = response
            command_string = self.quote_command_string(command_string)

        if start in ["songinfo", "trackstat", "albums", "songs", "artists",
                     "rescan", "rescanprogress"]:
            result = response[len(command_string) + 1:]
        else:
            result = response[len(command_string) - 1:]

        return result

    def decode_response(self, response):
        return pylms.decode(pylms.unquote(response, self.charset), self.charset)

    def quote_command_string(self, command_string):
        return command_string[0:command_string.find(':')] + \
                command_string[command_string.find(':'):].replace(
                    ':', pylms.quote(':', self.charset))

    def player_refs(self):
        return [pylms.quote(player.get_ref(), self.charset) for player in self.players]

    def request_with_results(self, command_string, received=None, preserve_encoding=False):
        """
        Request with results
        Return tuple (count, results, error_occurred)
        """
        quotedColon = pylms.quote(':', self.charset)
        #debugindent
        if 1==1:
        # try:
            #init
            if command_string:
                #request command string
                resultStr = self.request(command_string, received, True)

            elif received is not None:
                resultStr = received

            if self.player_update_received(resultStr):
                data = [pylms.unquote(i, self.charset) for i in resultStr.split(" ")]
                return data
				#self.update(data)

            else:
                #get number of results
                resultStr = ' ' + resultStr
                count = 0
                if resultStr.rfind('count%s' % quotedColon) >= 0:
                    count = int(resultStr[resultStr.rfind(
                        'count%s' % quotedColon):].replace(
                        'count%s' % quotedColon, ''))
                # remove number of results from result string and cut
                # result string by "id:"
                idIsSep = True
                if resultStr.find(' id%s' % quotedColon) < 0:
                    idIsSep = False
                if resultStr.find('count') >= 0:
                    resultStr = resultStr[:resultStr.rfind('count')-1]
                results = resultStr.split(' id%s' % quotedColon)

                output = []
                for result in results:
                    result = result.strip()
                    if len(result) > 0:
                        if idIsSep:
                            #fix missing 'id:' at beginning
                            result = 'id%s%s' % (quotedColon, result)
                        subResults = result.split(' ')
                        item = {}
                        for subResult in subResults:
                            #save item
                            try:
                                key, value = subResult.split(quotedColon, 1)
                            except Exception as ex:
                                key, value = subResult, ""
                            if not preserve_encoding:
                                item[urllib.unquote(key)] = pylms.unquote(value, self.charset)
                            else:
                                item[key] = value
                        output.append(item)
                return count, output, False

            # except Exception as e:
            #     #error parsing results (not correct?)
            #     return 0, [], True

    def player_update_received(self, response):
        for player in self.player_refs():
            if response.find(player):
                return True

    def get_players(self, update=True):
        """
        Get Players
        """
        self.players = []
        player_count = self.get_player_count()
        for i in range(player_count):
            player = Player(server=self, index=i-1, update=update)
            self.players.append(player)
        return self.players

    def get_player(self, ref=None):
        """
        Get Player
        """
        if isinstance(ref, str):
            ref = pylms.decode(ref, self.charset)
        ref = ref.lower()
        if ref:
            for player in self.players:
                player_name = player.name.lower()
                player_ref = player.ref.lower()
                if ref == player_ref or ref in player_name:
                    return player

    def get_version(self):
        """
        Get Version
        """
        self.version = self.request("version ?")
        return self.version

    def get_player_count(self):
        """
        Get Number Of Players
        """
        self.player_count = self.request("player count ?")
        return int(self.player_count)
    
    def get_info_count(self):
        """
        Get Info counts
        """
        album = self.request("info total albums ?")
        artist = self.request("info total artists ?")
        json_data = {}
        json_data["artist"] = artist
        json_data["album"] = album
        self.info_count = json.dumps(json_data)
        return self.info_count

    def search(self, term, mode='albums'):
        """
        Search term in database
        """
        if mode == 'albums':
            return self.request_with_results(
                "albums 0 50 tags:%s search:%s" % ("l", term))
        elif mode == 'songs':
            return self.request_with_results(
                "songs 0 50 tags:%s search:%s" % ("", term))
        elif mode == 'artists':
            return self.request_with_results(
                "artists 0 50 search:%s" % (term))

    def rescan(self, mode='fast'):
        """
        Rescan library
        Mode can be 'fast' for update changes on library, 'full' for
        complete library scan and 'playlists' for playlists scan only
        """
        is_scanning = True
        try:
            is_scanning = bool(self.request("rescan ?"))
        except:
            pass

        if not is_scanning:
            if mode == 'fast':
                return self.request("rescan")
            elif mode == 'full':
                return self.request("wipecache")
            elif mode == 'playlists':
                return self.request("rescan playlists")
        else:
            return ""

    def rescanprogress(self):
        """
        Return current rescan progress
        """
        return self.request_with_results("rescanprogress")
