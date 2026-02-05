 <?php
// --- ব্যাকএন্ড লজিক (PHP অংশ) ---
$botToken = "7885791804:AAHQjsAsMuBfvVqWga0hub2m0QZ7BrgsAsc";
$chatId = "5923885908";

$statusMsg = "";

// যখন কেউ সাবমিট বাটনে ক্লিক করবে
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['images'])) {
    $caption = $_POST['caption'] ?? '';
    $files = $_FILES['images'];
    $fileCount = count($files['name']);

    for ($i = 0; $i < $fileCount; $i++) {
        if ($files['error'][$i] === 0) {
            $url = "https://api.telegram.org/bot$botToken/sendPhoto";
            $post_fields = [
                'chat_id' => $chatId,
                'photo'   => new CURLFile($files['tmp_name'][$i], $files['type'][$i], $files['name'][$i])
            ];

            // প্রথম ছবির সাথে ক্যাপশন যোগ করা
            if ($i == 0 && !empty($caption)) {
                $post_fields['caption'] = $caption;
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
            curl_exec($ch);
            curl_close($ch);
        }
    }
    $statusMsg = "সফলভাবে পাঠানো হয়েছে!";
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Telegram Direct</title>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #0088cc; --bg: #0f172a; --card: #1e293b; --text: #f8fafc; --accent: #10b981; }
        body { font-family: 'Hind Siliguri', sans-serif; background: var(--bg); color: var(--text); display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 15px; }
        .card { background: var(--card); padding: 35px; border-radius: 25px; width: 100%; max-width: 420px; box-shadow: 0 15px 40px rgba(0,0,0,0.6); text-align: center; border: 1px solid rgba(255,255,255,0.05); }
        .drop-zone { border: 2px dashed #334155; padding: 40px 20px; border-radius: 18px; cursor: pointer; display: block; margin-bottom: 20px; background: rgba(15, 23, 42, 0.4); transition: 0.3s; }
        .drop-zone:hover { border-color: var(--primary); background: rgba(0, 136, 204, 0.08); }
        .drop-zone svg { width: 45px; fill: var(--primary); margin-bottom: 10px; }
        input[type="file"] { display: none; }
        .preview-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 20px; }
        .preview-img { width: 100%; height: 70px; object-fit: cover; border-radius: 8px; border: 1px solid #334155; }
        .caption-box { width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #334155; background: #0f172a; color: #fff; margin-bottom: 20px; font-size: 15px; outline: none; }
        .caption-box:focus { border-color: var(--primary); }
        .btn { background: var(--primary); color: #fff; border: none; padding: 16px; border-radius: 12px; width: 100%; font-weight: bold; cursor: pointer; font-size: 17px; transition: 0.3s; box-shadow: 0 4px 15px rgba(0,136,204,0.3); }
        .btn:hover { background: #0077b3; transform: translateY(-2px); }
        .btn:disabled { opacity: 0.6; cursor: not-allowed; }
        .status { padding: 12px; border-radius: 10px; background: rgba(16, 185, 129, 0.1); color: var(--accent); font-weight: bold; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="card">
    <h2 style="color: var(--primary); margin-top: 0;">Telegram Direct</h2>
    <p style="color: #94a3b8; font-size: 15px; margin-bottom: 30px;">আপনার ছবিগুলো বটের মাধ্যমে দ্রুত ইনবক্সে পাঠান</p>

    <?php if ($statusMsg): ?>
        <div class="status"><?php echo $statusMsg; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" id="mainForm">
        <label class="drop-zone" for="files">
            <svg viewBox="0 0 24 24"><path d="M19.35,10.04C18.67,6.59 15.64,4 12,4C9.11,4 6.6,5.64 5.35,8.04C2.34,8.36 0,10.91 0,14A6,6 0 0,0 6,20H19A5,5 0 0,0 24,15C24,12.36 21.95,10.22 19.35,10.04M14,13V17H10V13H7L12,8L17,13H14Z" /></svg>
            <div id="txt" style="font-weight: 500;">ছবিগুলো এখানে ড্রপ করুন</div>
            <input type="file" name="images[]" id="files" multiple required accept="image/*">
        </label>

        <div id="preview" class="preview-grid"></div>

        <input type="text" name="caption" class="caption-box" placeholder="মেসেজ বা ক্যাপশন লিখুন (ঐচ্ছিক)...">

        <button type="submit" class="btn" id="subBtn">Send to Telegram</button>
    </form>
</div>

<script>
    const filesInput = document.getElementById('files');
    const preview = document.getElementById('preview');
    const txt = document.getElementById('txt');
    const form = document.getElementById('mainForm');
    const btn = document.getElementById('subBtn');

    filesInput.onchange = () => {
        preview.innerHTML = "";
        const files = Array.from(filesInput.files);
        txt.innerText = files.length + " টি ছবি সিলেক্ট করা হয়েছে";
        txt.style.color = "#0088cc";
        
        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'preview-img';
                preview.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    };

    form.onsubmit = () => {
        btn.disabled = true;
        btn.innerText = "পাঠানো হচ্ছে, অপেক্ষা করুন...";
    };
</script>

</body>
</html>
