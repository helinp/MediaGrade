#!/bin/bash
max=12
for i in `seq 1 $max`
do
    curl -H "Content-Type: application/json" -X POST -d @edgeCases/$i.json 192.168.56.105:3003
done
