#!/bin/bash
inotifywait -m -r -e modify,attrib,close_write,move,create,delete ~/syncTest/ && echo "something new"
