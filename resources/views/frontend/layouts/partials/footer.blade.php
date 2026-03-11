 <!-- FOOTER -->
   @php
       $footerBgColor = $footerAbout->background_color ?? '#1a1a1a';
       $footerTextColor = $footerAbout->text_color ?? '#ffffff';
   @endphp
   
   <footer class="site-footer" style="background-color: {{ $footerBgColor }}; color: {{ $footerTextColor }};">
    <div class="footer-container">

        <!-- ABOUT -->
        <div class="footer-col">
            <h3 style="color: {{ $footerTextColor }};">{{ $footerAbout->title ?? 'Report System' }}</h3>
            <p style="color: {{ $footerTextColor }};">
                {{ $footerAbout->content ?? 'A secure and reliable platform to submit and manage reports. Your information helps build transparency and accountability.' }}
            </p>
        </div>

        <!-- LINKS -->
        <div class="footer-col">
            <h4 style="color: {{ $footerTextColor }};">Quick Links</h4>
            <ul>
                <li><a href="{{ route('home') }}" style="color: {{ $footerTextColor }}; opacity: 0.85;">Home</a></li>
                <li><a href="#report-form" style="color: {{ $footerTextColor }}; opacity: 0.85;">Submit Report</a></li>
                <li><a href="#report-table" style="color: {{ $footerTextColor }}; opacity: 0.85;">Search Reports</a></li>
            </ul>
        </div>

        <!-- CONTACT -->
        <div class="footer-col">
            <h4 style="color: {{ $footerTextColor }};">{{ $footerAbout->contact_title ?? 'Contact' }}</h4>
            <p style="color: {{ $footerTextColor }};">Email: {{ $footerAbout->email ?? 'support@example.com' }}</p>
            <p style="color: {{ $footerTextColor }};">Phone: {{ $footerAbout->phone ?? '123456789' }}</p>
            <p style="color: {{ $footerTextColor }};">Location: {{ $footerAbout->location ?? 'XYZ' }}</p>
        </div>

    </div>

    <div class="footer-bottom" style="color: {{ $footerTextColor }}; border-top-color: {{ $footerTextColor }}33;">
        © {{ date('Y') }} Report System. All rights reserved.
    </div>
</footer>

