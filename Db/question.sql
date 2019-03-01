CREATE TABLE `question` (                                                              
    `id` int(11) NOT NULL AUTO_INCREMENT,                                                
    `quiz_id` int(11) NOT NULL,                                                          
    `value_text` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,   
    `value_int` int(11) DEFAULT NULL,                                                    
    `value_array` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,  
    `type` varchar(6) COLLATE utf8_unicode_ci NOT NULL,                               
    PRIMARY KEY (`id`)                                                              
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci