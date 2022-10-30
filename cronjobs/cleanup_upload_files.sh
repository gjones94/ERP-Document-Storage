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
        file_path=$path/$file
        file_create=`date -r $file_path +%s`;
        date_now=`date +%s`;
        difference=$(( ($date_now - $file_create) / 60))
        if [[ $difference -gt 5 ]]; then
            echo "$file was created more than 5 minutes ago";
            echo "Deleting $file";
            rm $file_path    
        else
            echo $file has not expired yet
        fi
    done
fi
echo "==================End Cron Job=================="
