# What Is NodeRegator

NodeRegator is an utterly simple tool to track basic traffic information across multiple domains.  NodeRegator is not a replacement for GA, Piwik, Hummingbird or anything else you think it may be for.

NodeRegator is now split into two pieces. One is a dead simple node.js server that handles page "hits". The other is a PHP (yeah, I know) application that uses the Xoket framework. These can, and should, run on separate servers.

# Why NodeRegator

I have a bunch of sites spread across multiple GA accounts, and I want to compare them. Nothing fancy, just really basic information. This started as my quick fix for that, and has grown out of control.

# Requirements

* [node.js](http://nodejs.org/)
* [MongoDB](http://www.mongodb.org)
* [Xoket](http://github.com/jmhobbs/Xoket)
