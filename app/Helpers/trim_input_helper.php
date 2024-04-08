<?php

function trimAllPostInput($post)
{
     $data = [];
     foreach ($post as $key => $value) {
          $data[$key] = trim($value);
     }
     return $data;
}
