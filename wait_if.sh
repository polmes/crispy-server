while true; do
	if [ inotifywait -e modify,attrib,close_write,move,create,delete ~/scripts/ ]; then
		echo "update"
	else
		echo "nope"
	fi
	
	sleep 5
done
