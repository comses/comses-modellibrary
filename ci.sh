# $1: compressed filename
# $2: folder where compressed file resides
# $3: path to repository
# $4: Commit message
# $5: Any other parameters to be passed to "svn import"

#!/bin/bash
cd ${2}
mkdir trunk 2> /dev/null

# if extension is tar.gz or .tar
tar -C trunk -xf ${1}
# TODO: Write support for other compression formats (.zip, .gz, .bz2, .tar.bz2)

dirName=trunk
dirCount=`ls -l trunk | grep ^d | wc -l`
fileCount=`ls -l trunk | wc -l`
fileCount=`echo ${fileCount}-1 | bc`

if [ $dirCount -eq $fileCount ]		# number of files = number of directories
then
	if [ $dirCount -eq 1 ]				# and number of directories = 1
	then
		cd trunk
		dirName=`ls`	# get the directory name
	fi
fi

touch op
# now do an svn import
svn import -q $dirName ${3} -m \"${4}\" ${5} 2> ../op

if [ $? -ne 0 ]
then
	echo "There was an error importing the files. Please see the error message below:"
	cat ../op
else
	echo "OK"
	cd ../..
	rm -rf ${2}
fi
