@php
    $excerpt  = \Illuminate\Support\Str::limit(html_entity_decode(strip_tags($post->description)), 250);
    $readTime = max(1, (int) ceil(str_word_count(strip_tags($post->description)) / 200));
    $initials = strtoupper(mb_substr($post->author->name ?? 'A', 0, 2));
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>New Story: {{ $post->title }}</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital@1&display=swap" rel="stylesheet">
</head>
<body style="margin:0;padding:30px 16px;background:#ece6f5;font-family:'Inter',-apple-system,'Segoe UI',Helvetica,Arial,sans-serif;color:#1f1b2e;">

<!-- Mail window -->
<div style="max-width:1040px;margin:0 auto;background:#faf6ff;border-radius:12px;box-shadow:0 4px 24px rgba(80,60,140,0.10);overflow:hidden;">

  <!-- Email header -->
  <div style="padding:20px 28px 22px 28px;border-bottom:1px solid #eee8f6;background:#faf5ff;">
    <div style="font-size:19px;font-weight:500;color:#1f1b2e;margin-bottom:10px;">New Story: {{ $post->title }}</div>
    <div style="font-size:13px;color:#6b6680;">
      <span style="margin-right:6px;">To:</span>
      <span style="background:#f0ebfa;border-radius:12px;padding:3px 10px;font-size:13px;color:#4a4560;">{{ $emails }}</span>
    </div>
  </div>

  <!-- Body -->
  <div style="padding:40px 0;background:#faf6ff;">

    <!-- Newsletter card -->
    <div style="max-width:800px;margin:0 auto;background:#ffffff;border:1px solid #e9e3f3;border-radius:16px;overflow:hidden;">

      <!-- Masthead -->
      <div style="padding:40px 44px 28px 44px;border-bottom:1px solid #f0ebf7;">
        <div style="text-align:center;">
          <div style="font-size:35px;font-weight:600;letter-spacing:-1px;color:#1f1b2e;">SlowLiving Blog</div>
          <div style="font-family:'Playfair Display',Georgia,'Times New Roman',serif;font-style:italic;font-size:17px;color:#3d3950;margin-top:10px;">"Quiet contemplations for mindful modern living."</div>
          <div style="width:60px;height:3px;background:#c9c7f2;margin:22px auto 0 auto;border-radius:2px;"></div>
        </div>
      </div>

      <!-- Greeting -->
      <div style="padding:38px 44px 0 44px;">
        <div style="font-size:18px;color:#1f1b2e;margin-bottom:12px;">Hello,</div>
        <div style="font-size:16.5px;line-height:1.65;color:#4a4560;">A new story has just been published on SlowLiving Blog. Here is a short preview.</div>
      </div>

      <!-- Featured story -->
      <div style="margin:38px 44px 0 44px;border:1px solid #e9e3f3;border-radius:14px;overflow:hidden;background:#ffffff;">

        <!-- Post image -->
        @if ($post->image)
          <img src="{{ url('posts-images/' . $post->image) }}" alt="{{ $post->title }}"
               style="display:block;width:100%;height:auto;max-height:400px;object-fit:cover;">
        @endif

        <div style="padding:30px;">

          <!-- Category and reading time -->
          <div style="margin-bottom:16px;">
            <span style="display:inline-block;background:#e4e2fb;color:#3f3fd6;font-size:13px;font-weight:600;padding:5px 14px;border-radius:20px;">{{ $post->category->name ?? 'Story' }}</span>
            <span style="font-size:13px;color:#6b6680;margin-left:10px;">{{ $readTime }} min read</span>
          </div>

          <!-- Author -->
          <div style="margin-bottom:14px;">
            <span style="display:inline-block;width:38px;height:38px;line-height:38px;border-radius:50%;background:#e4e2fb;color:#3f3fd6;font-size:13px;font-weight:600;text-align:center;vertical-align:middle;margin-right:10px;">{{ $initials }}</span>
            <span style="display:inline-block;vertical-align:middle;">
              <span style="display:block;font-size:15px;font-weight:600;color:#1f1b2e;">{{ $post->author->name ?? 'Unknown author' }}</span>
              <span style="display:block;font-size:13px;color:#6b6680;margin-top:2px;">{{ $post->date }}</span>
            </span>
          </div>

          <!-- Title -->
          <div style="font-size:28px;font-weight:600;line-height:1.35;letter-spacing:-0.5px;color:#1f1b2e;margin-bottom:18px;">{{ $post->title }}</div>

          <!-- Excerpt -->
          <div style="font-size:16.5px;line-height:1.65;color:#4a4560;padding-bottom:26px;border-bottom:1px solid #f0ebf7;">{{ $excerpt }}</div>

          <!-- Read button -->
          <p style="margin:26px 0 0 0;">
            <a href="{{ url('/single-view?post=' . $post->id) }}"
               style="display:inline-block;background:#6d5bd0;color:#ffffff;padding:12px 22px;border-radius:6px;text-decoration:none;font-weight:600;">
              Read the full story
            </a>
          </p>
        </div>
      </div>

      <!-- Footer -->
      <div style="padding:28px 44px 32px 44px;font-size:12px;line-height:1.6;color:#888;text-align:center;">
        This email was sent to {{ $emails }} because you subscribed to our newsletter.<br>
        <a href="{{ $unsubscribeUrl }}" style="color:#6d5bd0;">Unsubscribe</a>
      </div>

    </div>
  </div>
</div>

</body>
</html>