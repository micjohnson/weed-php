#!/bin/bash
wget http://micjohnson.com:8620/files/weed-fs-latest-linux-386.tar.gz
tar -xzf weed-fs-latest-linux-386.tar.gz
echo "starting servers";
mkdir /tmp/data1
mkdir /tmp/data2
./weed/weed master &
echo "started master";
sleep 2s;
./weed/weed volume -dir="/tmp/data1" -max=12  -mserver="localhost:9333" -port=8080 &
echo "started volume 1: max=12";
sleep 1s;
./weed/weed volume -dir="/tmp/data2" -max=12  -mserver="localhost:9333" -port=8081 &
echo "started volume 2: max=12";
sleep 1s;
echo "weed-fs master and volume servers running"
