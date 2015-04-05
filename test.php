<?php

for($i=0; $i<count($files['name']); $i++){
                        $tmp = $file['tmp_name'][$i];
                        $data = file_get_contents($tmp);
                        $image = base64_encode($data);
                        $newImage = array(
                                'file' => array(
                                        'name' => $name,
                                        'content' => $image,
                                        'mime'    => 'image/png'
                                ),
                                'label'    => $name,
                                'position' => $i + 1,
                                'types'    => array('image', 'small_image', 'thumbnail'),
                                'exclude'  => 0
                        );
                        $client->call($session, 'product_media.create', array($code, $newImage));
                    }