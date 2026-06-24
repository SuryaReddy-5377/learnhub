<?php
// Create a simple default course image
$img = imagecreate(400, 300);
$bg = imagecolorallocate($img, 108, 60, 225);
$text_color = imagecolorallocate($img, 255, 255, 255);
imagestring($img, 5, 120, 130, "📚 Course", $text_color);
imagejpeg($img, 'assets/uploads/courses/default-course.jpg', 90);
imagedestroy($img);
echo "✅ Default course image created!";
?>