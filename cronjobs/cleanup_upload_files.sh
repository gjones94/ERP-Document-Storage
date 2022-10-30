#!/bin/bash

echo "================Running Cron job================"
echo "==========$(date)=========="

path="/var/www/html/doc_storage/uploads"

files=($(ls $path))

num_files=${#files[@]}

if [[ $num_files == 0 ]];then
    echo "No files to delete"
else
    for file in $files; do
        file=$path/$file
        file_create=`date -r $file +%s`;
        date_now=`date +%s`;
        difference=$(( ($date_now - $file_create) / 60))
        if [[ $difference -gt 1 ]]; then
            echo "$file was created more than 5 minutes ago";
            echo "Deleting $file";
            rm $file    
        else
            echo $file not old enough to delete
        fi
    done
fi
echo "==================End Cron Job=================="
