#!/bin/bash

# Call with ./lists2json.sh /path/to/graph.db nodeid /path/to/output.json

listsfilename=$1 

function getIndents () {
	tabs=""
      for i in `seq 0 $1`;
        do
                tabs="$tabs\t"
        done
}

function returnFields() {
	# find line number of search hit with grep -n: https://stackoverflow.com/questions/31854479/bash-find-line-number
	idlinenumber=$(grep -nP "\bID: $1(\s|$)" "$listsfilename" | cut -d: -f 1)
	# print nth line of a file: https://ubuntuforums.org/showthread.php?t=390568&p=2336580#post2336580
	# remove spaceswith sed: https://samindaw.wordpress.com/tag/remove-leading-and-trailing-spaces-in-strings-using-bash-script/
	name=$(sed -n "$[idlinenumber-2]p" $listsfilename | cut -d: -f2 | sed 's/^ *//g' | sed 's/ *$//g')
	type=$(sed -n "$[idlinenumber-1]p" $listsfilename | cut -d: -f2 | sed 's/^ *//g' | sed 's/ *$//g')	
	deps=$(sed -n "$[idlinenumber+1]p" $listsfilename | cut -d: -f2 | tr -d ',' | sed 's/^ *//g' | sed 's/ *$//g') # remove commas with tr -d: https://stackoverflow.com/questions/12668020/removing-characters-from-grep-output
}

function returnValues() {
	getIndents $numberoftabs
	counter=$((counter + 1))
	echo -e $tabs'id: "'$1_$counter'",' >>  $jsonfilename
	returnFields $1
	echo -e $tabs'name: "'$name'",' >>  $jsonfilename
	echo -e $tabs'data: {},' >>  $jsonfilename
	echo -e $tabs'children: [' >>  $jsonfilename
	if [[ ! -z $deps ]]
		then
			for dep in $deps 
				do
					echo -e $tabs'{'  >>  $jsonfilename
					numberoftabs=$((numberoftabs + 1))
					returnValues $dep
					numberoftabs=$((numberoftabs - 1))
					getIndents $numberoftabs
					echo -e $tabs"}," >>  $jsonfilename
			done
	fi
	echo -e $tabs ']'  >>  $jsonfilename
}

if [[ -z $(grep "Dependency lists file generated from" $1) ]]
	then
		echo "Not a dependency list db file, aborting..."
	else		
		# get file names from the original dot file and the corresponding db file
		dbfilename=$(basename $1)
		# strip file extension: https://stackoverflow.com/questions/965053/extract-filename-and-extension-in-bash
		# default value for empty arguments: http://cmattoon.com/default-parameter-values-in-bash/
		## jsonfilename=${3:-"${dbfilename%.*}_$2.json"}	
		# jsonfilename=${dbfilename%.*}_$2.json
		jsonfilename="$3"
		echo $jsonfilename
		# Remove old db file before generating a new one
		if [ -f $jsonfilename ]
			then
				rm $jsonfilename
		fi
		# Create empty db file
		touch $jsonfilename
		returnFields $2
		echo -e "var title = {TITLE: '$name'};" >>  $jsonfilename
		echo -e "var json = {" >>  $jsonfilename
		numberoftabs=0
		counter=0
		returnValues $2 # >>  $jsonfilename
		echo -e "}" >>  $jsonfilename
fi
