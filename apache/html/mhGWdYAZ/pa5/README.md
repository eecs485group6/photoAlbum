PA5
===

To test, please go to our server to test large.net, or use small.net or naive.net (the example covered in class) to test the program.

1. PageRank
-----------
To compile and distribute jar file
```
ant dist
```

To execute
```
eecs485pa5p 0.85 -k 10 small.net pagerankSmallOut
eecs485pa5p 0.85 -converge 0.01 small.net pagerankSmallOut
```

2. Hits
----
To compile and distribute jar file
```
ant dist
```

To execute
```
./eecs485pa5h 100 -k 10 "iPhone Microsoft" hits.net hits.inv output
./eecs485pa5h 50 -converge 0.01 "iPhone Microsoft" hits.net hits.inv output

```

3. Specification
-----------------

Follow the basic style of ant project, all class file is in `build/`, all jar file is in `dist/` and all source files are in `src/` folder.

Presented By: Group 6  Members
==============================

Qi Liao
Haixin Li
Yan Wang


