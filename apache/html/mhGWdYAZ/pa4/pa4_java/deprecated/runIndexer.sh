#!/bin/sh

java -cp dist/lib/pa4.jar:lib/json-simple-1.1.1.jar edu.umich.eecs485.pa4.Indexer captions test.txt > test.txt
