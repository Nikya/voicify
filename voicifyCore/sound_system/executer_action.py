#!/usr/bin/env python
import sys
from pylms import server
from pylms import player
import json
import os
import time
import requests
import hashlib
import os.path
from pydub import AudioSegment
from gtts import gTTS

def stop(player):
    result=player.stop()
    return result

def eteint(player):
    result=player.set_power_state(0)
    return result

def allume(player):
    result=player.set_power_state(1)
    return result

def lecture(player):
    result=player.play()
    return result

def pause(player):
    result=player.pause()
    return result

def muet(player):
    result=player.mute()
    return result

def nomuet(player):
    result=player.unmute()
    return result

def suivant(player):
    result=player.next()
    return result

def precedent(player):
    result=player.prev()
    return result

def avance(player):
    result=player.forward(seconds=15)
    return result

def recule(player):
    result=player.rewind(seconds=15)
    return result

def setvolume(player):
    result=player.set_volume(sys.argv[7])
    return result

def changevolume(player):
    actual=player.get_volume()
    new=actual+int(sys.argv[7])
    result=player.set_volume(new)
    return result

def sync_depuis(player):
    if sys.argv[8]=="true":
       player.set_power_state(1)
    result=player.sync_to(sys.argv[7])
    return result

def desync(player):
    result=player.unsync()
    if sys.argv[7]=='true':
       player.set_power_state(0)
    return result

def parle(player):
    cachepath=os.path.abspath(os.path.join(os.path.join(os.path.dirname(os.path.dirname(__file__)), 'tmp'), 'cache'))
    tmppath=os.path.abspath(os.path.join(os.path.dirname(os.path.dirname(__file__)), 'tmp'))
    try:
        os.stat(tmppath)
    except:
        os.mkdir(tmppath)
    jinglepath=os.path.abspath(os.path.join(os.path.dirname(__file__), 'jingle'))
    file = hashlib.md5(sys.argv[11]+sys.argv[12]+sys.argv[10]+sys.argv[7]).hexdigest()
    found = 0
    filename=os.path.join(cachepath,'tts.wav')
    filenamemp3=os.path.join(cachepath,file+'.mp3')
    if not os.path.isfile(filenamemp3):
        try:
            os.stat(cachepath)
        except:
            os.mkdir(cachepath)
        if sys.argv[11]== 'picotts':
            os.system('pico2wave -l '+sys.argv[12]+' -w '+filename+ ' "' +sys.argv[7]+ '"')
            song = AudioSegment.from_wav(filename)
        elif sys.argv[11]== 'google':
            tts = gTTS(text=sys.argv[7], lang=sys.argv[12])
            tts.save(filenamemp3)
            song = AudioSegment.from_mp3(filenamemp3)
        elif sys.argv[11]== 'voxygen':
            mp3file = requests.get('http://www.voxygen.fr/sites/all/modules/voxygen_voices/assets/proxy/index.php?method=redirect&text='+sys.argv[7]+'&voice='+sys.argv[12]+'&ts=14030902642', stream=True)
            output = open(filenamemp3,'wb')
            output.write(mp3file.content)
            output.close()
            song = AudioSegment.from_mp3(filenamemp3)
        if sys.argv[10] == 'nojingle':
            songmodified=song
        else:
            jinglename=os.path.join(jinglepath,sys.argv[10]+'.mp3')
            jingle= AudioSegment.from_mp3(jinglename)
            songmodified = jingle+song
        songmodified.export(filenamemp3, format="mp3", bitrate="128k", tags={'albumartist': 'Jeedom', 'title': 'TTS', 'artist':'Jeedom'}, parameters=["-ar", "44100","-vol", "1200"])
    song = AudioSegment.from_mp3(filenamemp3)
    songtime=song.duration_seconds
    urltoplay=sys.argv[8]+'/sandbox/tmp/cache/'+file+'.mp3'
    modeinit=player.get_mode()
    powerinit=player.get_power_state()
    actualvolume=player.get_volume()
    actualshufflestate=player.get_playlist_shuffle_state()
    tempplaylistname='temp_'+player.get_ref().replace(':','')
    player.set_playlist_shuffle_state(0)
    player.playlist_save(tempplaylistname)
    positionlecture=player.get_time_elapsed()
    if sys.argv[9] != 'nochange':
        player.set_volume(int(sys.argv[9]))
    result=player.playlist_play(urltoplay)
    time.sleep(songtime+1.2)
    player.playlist_play_playlist(tempplaylistname)
    player.set_volume(0)
    player.seek_to(positionlecture)
    if powerinit == False:
        player.set_power_state(0)
    else:
        if modeinit=='stop':
            player.stop()
        elif modeinit=='pause':
            time.sleep(2)
            player.pause()
            time.sleep(0.5)
    player.set_volume(actualvolume)
    player.set_playlist_shuffle_state(actualshufflestate)
    return result

def lireartist(player):
    resultat=s.search(sys.argv[7],'artists')
    result=p.playlist_play_artist(resultat[1][7:])
    return result

def lirealbum(player):
    resultat=s.search(sys.argv[7],'albums')
    result=p.playlist_play_album(resultat[1][6:])
    return result

def liregenre(player):
    result=p.playlist_play_genre(sys.argv[7])
    return result

def lireplaylist(player):
    result=p.playlist_play_playlist(sys.argv[7])
    return result

def lireurl(player):
    result=p.playlist_play(sys.argv[7])
    return result

results={}
actions = {"parle" : parle
}
s = server.Server(hostname=sys.argv[1], port=sys.argv[2], username=sys.argv[3], password=sys.argv[4])
s.connect()

p = s.get_player(sys.argv[5])

actions[sys.argv[6]](p)
