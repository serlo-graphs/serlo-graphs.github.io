#!/bin/bash

# Call with: ./dot2lists.sh /path/to/graph.dot (assume location ../dot/graph.dot)

# TODO: Proper comment parsing

# Check if we have a digraph type dot file, abort if not
if [[ -z $(grep digraph $1)  ]]
	then
		echo "Not a digraph dot file, aborting..."
	else
    mypath=$(cd "${0%/*}" 2>/dev/null; echo "$PWD"/"${0##*/}")
    scriptdir=$(dirname $mypath)
    completedir=${scriptdir%/*}
		# get file names from the original dot file and the corresponding db file
		dotfilename=$(basename $1)
		dbfilename="${dotfilename%.*}.db" # strip file extension: https://stackoverflow.com/questions/965053/extract-filename-and-extension-in-bash		
    dbfilepath=$completedir/db/$dbfilename
		# Remove old db file before generating a new one
		if [ -f $dbfilepath ]
			then
				rm $dbfilepath
		fi
		# Create empty db file
		touch $dbfilepath
		echo -e "Dependency lists file generated from $dotfilename\n" >> $dbfilepath
		# Only separate at line breaks in FOR loops: https://askubuntu.com/questions/344407/how-to-read-complete-line-in-for-loop-with-spaces
		IFS=$'\n' 
		for node in $(grep label $1 | grep -v "//") # filter all lines including comments: https://stackoverflow.com/questions/3548453/negative-matching-using-grep-match-lines-that-do-not-contain-foo
			do
				label=$(echo $node | grep -oP 'label="\K[^"]+') # grep between 'label="' and first '"': https://stackoverflow.com/questions/26185456/extract-text-between-2-strings-only-till-first-occurence-of-end-stringhttps://stackoverflow.com/questions/26185456/extract-text-between-2-strings-only-till-first-occurence-of-end-string
				label=$(echo $label | sed -e 's/[\\][n]/ /') # remove literal \n: https://stackoverflow.com/questions/19762365/sed-help-matching-and-replacing-a-literal-n-not-the-newline
				shape=$(echo $node | grep -oP 'shape="\K[^"]+')
				if [ "$shape" == "note" ]
					then
						type="Theorem"
					else 
						type="Definition"
				fi
				id=$(echo $node | cut -d[ -f1) # string before [ sign: https://stackoverflow.com/questions/20348097/bash-extract-string-before-a-colon
				
				# print information to db file (-e option for correct \n and \t interpretation)
				echo -e "node{" >> $dbfilepath
				echo -e "\tLabel: $label" >> $dbfilepath
				echo -e "\tType: $type" >> $dbfilepath
				echo -e "\tID: $id" >> $dbfilepath
				
				# make grep work with > sign: https://stackoverflow.com/questions/15836619/how-do-i-grep-for-a-greater-than-symbol
				# only exact id matches: https://stackoverflow.com/questions/42454312/getting-only-grep-exact-matches
				deplist=""
				for dep in $(grep -P -- "\b->$id(\s|$)" $1 | grep -v "//"|  cut -d- -f1)
					do
						deplist="$deplist, $dep"
				done
				deplist=$(echo "$deplist" | cut -c 2- | sed 's/^ *//g' | sed 's/ *$//g') # cut leading comma (first to chars) with cut: https://stackoverflow.com/questions/971879/what-is-a-unix-command-for-deleting-the-first-n-characters-of-a-line
				echo -e "\tDeps: $deplist" >> $dbfilepath
				echo -e "}\n" >> $dbfilepath
		done
fi