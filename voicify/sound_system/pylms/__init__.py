#!/usr/bin/env python

def encode(text, charset):
    return text.encode(charset)

def decode(bytes, charset):
    return bytes.decode(charset)

def quote(text, charset):
    try:
        import urllib.parse
        return urllib.parse.quote(text, encoding=charset)
    except ImportError:
        import urllib
        return urllib.quote(text)

def unquote(text, charset):
    try:
        import urllib.parse
        return urllib.parse.unquote(text, encoding=scharset)
    except ImportError:
        import urllib
        return urllib.unquote(text)