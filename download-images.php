<?php
// Create uploads folder if not exists
$upload_dir = 'assets/uploads/courses/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
    echo "📁 Created folder: $upload_dir<br>";
}

// List of FREE image URLs
$images = [
    'web-development.jpg' => 'https://img.freepik.com/free-vector/website-development-banner_33099-1687.jpg',
    'data-science.jpg' => 'https://img.freepik.com/free-vector/data-analysis-banner_33099-1670.jpg',
    'mobile-dev.jpg' => 'https://img.freepik.com/free-vector/mobile-app-development-banner_33099-1720.jpg',
    'cloud-computing.jpg' => 'https://img.freepik.com/free-vector/cloud-computing-banner_33099-1685.jpg',
    'design.jpg' => 'https://img.freepik.com/free-vector/graphic-design-banner_33099-1688.jpg',
    'business.jpg' => 'https://img.freepik.com/free-vector/business-management-banner_33099-1690.jpg',
    'artificial-intelligence.jpg' => 'https://img.freepik.com/free-vector/artificial-intelligence-banner_33099-1689.jpg',
    'cybersecurity.jpg' => 'https://img.freepik.com/free-vector/cyber-security-banner_33099-1691.jpg',
    'game-dev.jpg' => 'https://img.freepik.com/free-vector/game-development-banner_33099-1692.jpg',
    'project-management.jpg' => 'https://img.freepik.com/free-vector/project-management-banner_33099-1693.jpg',
    'marketing.jpg' => 'https://img.freepik.com/free-vector/digital-marketing-banner_33099-1694.jpg',
    'finance.jpg' => 'https://img.freepik.com/free-vector/finance-banner_33099-1695.jpg',
    'default-course.jpg' => 'https://img.freepik.com/free-vector/education-banner_33099-1686.jpg'
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
    
    // Download image using file_get_contents (simpler)
    $image_data = @file_get_contents($url);
    
    if ($image_data && strlen($image_data) > 1000) {
        file_put_contents($filepath, $image_data);
        echo "✅ <span style='color:green;'>Downloaded:</span> $filename (" . round(strlen($image_data)/1024) . " KB)<br>";
        $success++;
    } else {
        echo "❌ <span style='color:red;'>Failed:</span> $filename<br>";
        $failed++;
    }
}

echo "</div>";
echo "<hr>";
echo "<h3>Summary:</h3>";
echo "Success: $success images<br>";
echo "Failed: $failed images<br>";
echo "<br>Images saved in: <strong>$upload_dir</strong>";
echo "<br><br><a href='courses.php' class='btn btn-primary'>View Courses</a>";
?>