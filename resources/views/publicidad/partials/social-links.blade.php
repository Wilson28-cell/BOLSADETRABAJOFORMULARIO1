@php
    $socialText = trim($texto ?? '');
    $socialLinks = [];

    if ($socialText !== '') {
        $parts = preg_split('/[\r\n,;]+/', $socialText, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($parts as $part) {
            $rawValue = trim($part);
            if ($rawValue === '') {
                continue;
            }

            $url = $rawValue;
            if (!preg_match('/^https?:\/\//i', $url)) {
                $url = 'https://' . preg_replace('/^www\./i', '', $url);
            }

            $host = strtolower(parse_url($url, PHP_URL_HOST) ?: '');
            $icon = 'bi-link-45deg';

            if (str_contains($host, 'facebook.com')) {
                $icon = 'bi-facebook';
            } elseif (str_contains($host, 'instagram.com')) {
                $icon = 'bi-instagram';
            } elseif (str_contains($host, 'twitter.com')) {
                $icon = 'bi-twitter';
            } elseif (str_contains($host, 'tiktok.com')) {
                $icon = 'bi-tiktok';
            } elseif (str_contains($host, 'youtube.com') || str_contains($host, 'youtu.be')) {
                $icon = 'bi-youtube';
            } elseif (str_contains($host, 'linkedin.com')) {
                $icon = 'bi-linkedin';
            } elseif (str_contains($host, 'wa.me') || str_contains($host, 'whatsapp.com')) {
                $icon = 'bi-whatsapp';
            } elseif (str_contains($host, 'telegram.me') || str_contains($host, 't.me')) {
                $icon = 'bi-telegram';
            } elseif (str_contains($host, 'pinterest.com')) {
                $icon = 'bi-pinterest';
            } elseif (str_contains($host, 'github.com')) {
                $icon = 'bi-github';
            } elseif ($host !== '') {
                $icon = 'bi-globe';
            }

            $socialLinks[] = [
                'url' => $url,
                'label' => $rawValue,
                'icon' => $icon,
            ];
        }
    }
@endphp

<div class="social-links">
    @if(count($socialLinks) > 0)
        @foreach($socialLinks as $link)
            <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" class="social-link">
                <i class="bi {{ $link['icon'] }}"></i>
                <span>{{ $link['label'] }}</span>
            </a>
        @endforeach
    @else
        <span class="social-link-empty">No disponible</span>
    @endif
</div>
