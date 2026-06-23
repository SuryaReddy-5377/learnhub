<?php
// Create uploads folder
$upload_dir = 'assets/uploads/courses/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// List of image URLs for each course
$images = [
    'web-dev.jpg' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=400&h=300&fit=crop',
    'js-advanced.jpg' => 'https://images.unsplash.com/photo-1579468118864-1b9ea3c0db4a?w=400&h=300&fit=crop',
    'react-course.jpg' => 'https://images.unsplash.com/photo-1633356122102-3fe601e05bd2?w=400&h=300&fit=crop',
    'php-mysql.jpg' => 'https://images.unsplash.com/photo-1542831371-29b0f74f971d?w=400&h=300&fit=crop',
    'laravel.jpg' => 'https://images.unsplash.com/photo-1618401471353-b98afee0b2eb?w=400&h=300&fit=crop',
    'python-data.jpg' => 'https://images.unsplash.com/photo-1526379879527-8559ecfcaec0?w=400&h=300&fit=crop',
    'ml-course.jpg' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&h=300&fit=crop',
    'data-viz.jpg' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&h=300&fit=crop',
    'tensorflow.jpg' => 'https://images.unsplash.com/photo-1507146426996-ef05306b995a?w=400&h=300&fit=crop',
    'sql-data.jpg' => 'https://images.unsplash.com/photo-1544383835-bda2bc66a55d?w=400&h=300&fit=crop',
    'android-dev.jpg' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=400&h=300&fit=crop',
    'ios-swift.jpg' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=400&h=300&fit=crop',
    'react-native.jpg' => 'https://images.unsplash.com/photo-1551650975-87deedd944c3?w=400&h=300&fit=crop',
    'flutter.jpg' => 'https://images.unsplash.com/photo-1551650975-87deedd944c3?w=400&h=300&fit=crop',
    'aws-solutions.jpg' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=400&h=300&fit=crop',
    'azure-cloud.jpg' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=400&h=300&fit=crop',
    'docker-k8s.jpg' => 'https://images.unsplash.com/photo-1605745341112-85968b19335b?w=400&h=300&fit=crop',
    'devops.jpg' => 'https://images.unsplash.com/photo-1605745341112-85968b19335b?w=400&h=300&fit=crop',
    'ui-ux.jpg' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=400&h=300&fit=crop',
    'graphic-design.jpg' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=400&h=300&fit=crop',
    'photoshop.jpg' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=400&h=300&fit=crop',
    'figma.jpg' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=400&h=300&fit=crop',
    'digital-marketing.jpg' => 'https://images.unsplash.com/photo-1432888622747-4eb9a8f2c56a?w=400&h=300&fit=crop',
    'business-analytics.jpg' => 'https://images.unsplash.com/photo-1432888622747-4eb9a8f2c56a?w=400&h=300&fit=crop',
    'accounting.jpg' => 'https://images.unsplash.com/photo-1432888622747-4eb9a8f2c56a?w=400&h=300&fit=crop',
    'entrepreneurship.jpg' => 'https://images.unsplash.com/photo-1432888622747-4eb9a8f2c56a?w=400&h=300&fit=crop',
    'wordpress.jpg' => 'https://images.unsplash.com/photo-1488590528505-98d2b853aba4?w=400&h=300&fit=crop',
    'ai-fundamentals.jpg' => 'https://images.unsplash.com/photo-1488590528505-98d2b853aba4?w=400&h=300&fit=crop',
    'unity-game-dev.jpg' => 'https://images.unsplash.com/photo-1488590528505-98d2b853aba4?w=400&h=300&fit=crop',
    'cybersecurity.jpg' => 'https://images.unsplash.com/photo-1488590528505-98d2b853aba4?w=400&h=300&fit=crop',
    'blender-3d.jpg' => 'https://images.unsplash.com/photo-1488590528505-98d2b853aba4?w=400&h=300&fit=crop',
    'project-management.jpg' => 'https://images.unsplash.com/photo-1488590528505-98d2b853aba4?w=400&h=300&fit=crop'
];

echo "<h2>📚 Downloading Course Images...</h2>";
echo "<div style='font-family: Arial; font-size: 14px;'>";

$success = 0;
$failed = 0;

foreach ($images as $filename => $url) {
    $filepath = $upload_dir . $filename;
    
    // Skip if exists
    if (file_exists($filepath) && filesize($filepath) > 1000) {
        echo "✅ <span style='color:green;'>Already exists:</span> $filename<br>";
        $success++;
        continue;
    }
    
    // Download image
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $image_data = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code == 200 && $image_data && strlen($image_data) > 1000) {
        file_put_contents($filepath, $image_data);
        echo "✅ <span style='color:green;'>Downloaded:</span> $filename<br>";
        $success++;
    } else {
        echo "❌ <span style='color:red;'>Failed:</span> $filename<br>";
        $failed++;
    }
}

echo "</div>";
echo "<hr>";
echo "<h3>📊 Summary:</h3>";
echo "✅ Success: $success images<br>";
echo "❌ Failed: $failed images<br>";
echo "<br>📁 Images saved in: <strong>$upload_dir</strong>";
echo "<br><br><a href='courses.php' class='btn btn-primary'>📚 View Courses</a>";
?>