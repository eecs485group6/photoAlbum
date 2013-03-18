EECS485-Project
================
Program Assignment 4

Updated: March 16 2013

Notice: For simplicity, the image will now only display with url. The SPV functionality from PA3 is also not accessible.

Initialization
==============

1. Initialize mysql database:

Enter `sql` folder, and do `./initialization.sh`. It will drop all the table, and recreate all of them and insert all the data in the database except the 200 new photos in PA4 which will be added in the next step.

2. Extract the 200 photos from search.xml and add to database:

In your browser, type in `http://eecs485-02.eecs.umich.edu:4506/mhGWdYAZ/pa4/index/readxml.php` or, if you are using a different server, your own `$host:$port/path_to_our_pa4_folder/index/readxml.php`. The readxml.php will parse search.xml and insert all the element/attribute values into the respective tables in mysql.

The index/readxml.php page will refresh after 10 seconds to lead you to the homepage (index.php) of our photoalbum where you can click on the `SearchPhotos` link on the upper right corner of the page which will lead to the main page where you may search photos by words or by phrases.

Alternatively, you may run `php /index/readxml.php` directly under the pa4/ folder.

This php script will insert all data into mysql database. All photos are stored under 'search' album which has albumid = 5. 

NOTICE: Most of the search results will be processed on this album instead of previous albums for simplicity. 

PA4 Assignment 
==============

Get Started
-----------
0. prerequiste: insert data into mysql database

1. run the indexer by entering pa4_extra folder, then type 

'ant run-indexer -Din=captions -Dout=index.txt' 

You can check the indexer result in index.txt (For checking the normal inverted index tables, you may want to look into pa4_java/index.txt. This is the normal version (the index without the extra position information required by extra point 1) which has the exactly same result as the sample given in the handout. the new index.txt under pa4_extra saves extra position information without calculating any scores.)

2. Still under the pa4_extra folder, run the server by typing 

'ant run-server -Dport=2114 -Dfname=index.txt' 

Our port is 2114. The server will start.  

3. now you can check the website to do normal query/phrase query/similar photo query/change caption.

Design Overview
----------------------------------------------
In PA4, we used a data structure double nested hashmap. The stucture is like this:
```
word     doc contains the word     tf_doc
'as' ->     doc 404         ->      2    
            doc 138         ->      1
            doc 505         ->      1
'pool'-> ...
```

We have a hashmap of words. For each word, we have a hashmap which contains docid of doc that contains this word. the value of this hashmap is tf_doc, the term frequency of the word appearing in the doc. To get nk for calculation of idf, we simply aggregate tf_doc in each doc for this word.

Two types of queries are presented in this project. One is normal query, which query each single word in the querywords and aggregate each result. The other is phrase query, which will treat query as a whole; only photos that strictly contain the entire phrase in the right order can be returned. 

We filter out stopwords in this step. Stopwords will have 0 tfidf assigned. In normal query which allow AND operation to search the result, we will not use stopwords to find docs. On the other hand, when using phrase search which require strictly containing phrase in right order, we will still use stopwords to find docs, even through they have no weight to affect scores.
     
Search.php (DONE)
--------------------------------------

A link to search.php is on the header bar on the very right on our album webpages.

There are links on the top bar called 'search photos' to this page. you can process your queries on this page, by typing in your querywords and click on desired button. Normal query and phrase query are provided here. The query result is formatted in highest to lowest order of relavant score. 

finding similar pictures in viewpicture.php(DONE)
--------------------------------------------------
You can check each picture in this page and it will display all the similar pictures that have similar captions at the bottom of this page. We used normal query here instead of phrase query. As you may notice, the same picture will always display at the first position because it has highest relevant score (they are the same). 


Extra Credit (ALL DONE)
=======================
phrase queries(DONE)
--------------------------------
In this part, the data structure changed to be double nested hashmap that contains hashset as final layer. It is like this:
```
word     doc contains the word     positions in doc
'as' ->     doc 404         ->      0, 5    
            doc 138         ->      1
            doc 505         ->      3
'pool'->    doc 404         ->      6
...
```
It will save the position of the word that appears in the doc. Before each query, we will generate a list of docs that strictly contain the phrase by checking each position of the word.
For example, the query is 'as pool'. doc 404 has two as, positioning at 0 and 5. The function will check both 0 and 5 position, to see if pool appear in 1 and 6 that right after it. As shown above, pool does appear at position 6 in doc 404, therefore doc 404 is included in the list. The function will continue to check the relavant score of the doc.

Updating the index file in place(DONE)
-----------------------------------
To achieve this feature, we will not do the calculation at indexer. Instead, index server will process the query and do the calculation on the fly. Indexer created the datastructure as shown above. Index Server holds this strucure, and when new query is coming,it will search for the docs and calculate relavant scores. We saved the total tfidf of a doc that previously calculated in each process query function of index server to simplify the calculation. The total tfidf is calculated by the calTotal function.

When caption is updated in viewpicture.php, this page will update mysql database. If the albumid is 5, the page will send a request to the index server to change the values in the data structure. The index server will remove the entries of previous captions, and insert new captions into the datastructure. At last, the server will return an OK message to viewpicture.php to state that it successfully updates the index. 

Specification
=============
We stick with the version implemented for extra points as the server. However, you may also look into pa4_java to see the previous version we have done, which will calculate all the scores. It takes captions as index file.

Bugs we found
=============
we found that captions and search.xml have different entries. For example in doc 178, captions has 'and' while search.xml has '&' in the same position. This will make the indexer and database inconsisitent. 


Presented by: Group 6 Team Member
=================================
Qi Liao
Haixin Li
Yan Wang
