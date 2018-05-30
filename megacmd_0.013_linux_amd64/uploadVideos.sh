#!/bin/sh

search_dir="/app/Downloads"
chmod +x megacmd

for entry in $search_dir/* 
 do 
    echo -e "uploading file "$entry"\n"
    uploadQuery="./megacmd -conf megacmd.json put \"$entry\" mega:/"
    echo $uploadQuery
    eval $uploadQuery
 done