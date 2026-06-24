<?php
// Create uploads folder if not exists
$upload_dir = 'assets/uploads/courses/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// List of FREE image URLs for each course
$images = [
    'web-development.jpg' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=400&h=250&fit=crop',
    'data-science.jpg' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&h=250&fit=crop',
    'mobile-dev.jpg' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=400&h=250&fit=crop',
    'cloud-computing.jpg' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=400&h=250&fit=crop',
    'design.jpg' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=400&h=250&fit=crop',
    'business.jpg' => 'https://images.unsplash.com/photo-1432888622747-4eb9a8f2c56a?w=400&h=250&fit=crop',
    'artificial-intelligence.jpg' => 'https://images.unsplash.com/photo-1488590528505-98d2b853aba4?w=400&h=250&fit=crop',
    'cybersecurity.jpg' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=400&h=250&fit=crop',
    'game-dev.jpg' => 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?w=400&h=250&fit=crop',
    'project-management.jpg' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=400&h=250&fit=crop',
    'marketing.jpg' => 'https://images.unsplash.com/photo-1432888622747-4eb9a8f2c56a?w=400&h=250&fit=crop',
    'finance.jpg' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&h=250&fit=crop'
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
    
    // Download image using file_get_contents
    $image_data = @file_get_contents($url);
    
    if ($image_data && strlen($image_data) > 1000) {
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