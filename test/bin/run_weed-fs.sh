#!/bin/bash
wget http://micjohnson.com:8620/files/weed-fs-0.26-linux-386.tar.gz
tar -xzf weed-fs-0.26-linux-386.tar.gz
echo "starting servers";
mkdir /tmp/data1
./weed-fs/weed master &
echo "started master";
sleep 1s;
./weed-fs/weed volume -dir="/tmp/data1" -max=5  -mserver="localhost:9333" -port=8080 &
echo "started volume";
sleep 2s;
echo "weed-fs master and volume server running"
