#!/bin/bash

# Call with ./generate_all.sh /path/to/graph.db

mypath=$(cd "${0%/*}" 2>/dev/null; echo "$PWD"/"${0##*/}")
scriptdir=$(dirname $mypath)
completedir=${scriptdir%/*}
completedir=${completedir%/*}

dbfilename=$(basename $1)

inputlistpath=$1
templatepath="$completedir/graphviz/templates/template.html"
outputpath="$completedir/Graphs/${dbfilename%.*}"
scriptpath="$scriptdir/lists2json.sh"
indexdir="$completedir/Graphs/${dbfilename%.*}.html"

rm -r $outputpath
mkdir $outputpath

echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Spacetree - Graphs Index</title>

</head>

<body>
' >> $indexdir

ids=($(grep "ID: " $inputlistpath | cut -d: -f2))

for i in `seq 0 $((${#ids[@]} - 1))`
	do	
		id=${ids[$i]}
		idlinenumber=$(grep -nP "\bID: $id(\s|$)" "$inputlistpath" | cut -d: -f 1)
		name=$(sed -n "$[idlinenumber-2]p" $inputlistpath | cut -d: -f2 | sed 's/^ *//g' | sed 's/ *$//g')
		#echo $name $id
		htmlout=$outputpath/graph_$id.html
    jsonout=$outputpath/graph_$id.json
		cp $templatepath $htmlout
		sed -i "s|<script language=\"javascript\" type=\"text/javascript\" src=\"\"></script>|<script language=\"javascript\" type=\"text/javascript\" src=\"graph_$id.json\"></script>|" $htmlout
		$scriptpath "$inputlistpath" $id "$jsonout"
		echo "<p><a href=\"${dbfilename%.*}/graph_$id.html\">$name</a></p>" >> $indexdir
done

echo "</body>" >> $indexdir
echo "</html>" >> $indexdir