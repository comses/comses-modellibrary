
# $1: project name
# $2: user name
# $3: folder where files are to be extracted temporarily
# $4: path to repository
# $5: any other parameters to "svn co"

cd ${1}/${2}
mkdir $3 2> /dev/null
cd $3
svn -q co ${4}/${1} 2> op

retStat=$?

if [ $retStat -eq 0 ]
then
	echo "OK"
else
	echo "Error while extracting, see following line for details"
	cat op
	exit
fi

dirName=`ls`	# there has to be only a single directory
tar -cf myfiles.tar $dirName 2>> op

retStat=$?

rm -rf $dirName

if [ $retStat -eq 0 ]
then
	echo "OK"
else
	echo "Error while extracting, see following line for details"
	cat op
fi
