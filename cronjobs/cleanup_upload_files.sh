#!/bin/bash

path="/var/www/html/doc_storage/uploads"
files=$( ls $path )
for file in $files; do
    file=$path/$file
    file_create=`date -r $file +%s`;
    date_now=`date +%s`;
    difference=$(( ($date_now - $file_create) / 60))
    if [[ $difference -gt 5 ]]; then
        echo "$file was created more than 5 minutes ago";
        echo "Deleting $file";
        rm $file    
    fi

done
