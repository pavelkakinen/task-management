<?php

require_once 'socket.php';

$request = "GET /~makalm/icd0007/foorum/?message=deleted&username=pavelkakinen&key=89a9565493 HTTP/1.1
Host: enos.itcollege.ee
Cookie: PHPSESSID=6ni7jfg44s9a7kfnnjl0ov9j3o

";
//Cookie: PHPSESSID=6ni7jfg44s9a7kfnnjl0ov9j3o

print makeWebRequest("enos.itcollege.ee", 443, $request);
