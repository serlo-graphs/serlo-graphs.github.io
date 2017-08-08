#!/bin/bash

# TODO: Proper comment parsing


# Check if we have a digraph type dot file, abort if not
if [[ -z $(grep digraph $1)  ]]
	then
		echo "Not a digraph dot file, aborting..."
	else
		# get file names from the original dot file and the corresponding db file
		dotfilename=$(basename $1)
		dotsvgfilename="${dotfilename%.*}_svg.dot"
    
    mypath=$(cd "${0%/*}" 2>/dev/null; echo "$PWD"/"${0##*/}")
    scriptdir=$(dirname $mypath)
    completedir=${scriptdir%/*}
    completedir=${completedir%/*}
		# Remove old db file before generating a new one
		if [ -f $completedir/graphviz/dot/$dotsvgfilename ]
			then
				rm $completedir/graphviz/dot/$dotsvgfilename
		fi
		# Create empty db file
		cp $1 $completedir/graphviz/dot/$dotsvgfilename
		sed -i "s|\(^.*\)\[label|\1\[href=\"${dotfilename%.*}/graph_\1.html\", label|" $completedir/graphviz/dot/$dotsvgfilename
		dot -Tsvg $completedir/graphviz/dot/$dotsvgfilename -o $completedir/Graphs/${dotfilename%.*}.svg
fi