{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value @bagian title ke parent nya yaitu layouts.app dan @yield('title') --}}
@section('title', 'Dzikir')

{{-- @push berfungsi mendorong value nya lalu nanti ditangap oleh @stack('css') --}}
@push('css')

@endpush

{{-- kirimkan value @bagian('konten') lalu nanti akan ditangkap oleh @yield('konten') --}}
@section('konten')
<div class="row">
    <div class="col-sm-12">
        <h3>Dzikir Setelah Sholat Fardhu Lengkap</h3>
        <div class="mb-4">
            <p>1. Membaca Istighfar 3x</p>
            <p>أَسْتَغْفِرُ اللهَ الْعَظِـيْمِ الَّذِيْ لَااِلَهَ اِلَّا هُوَ الْحَيُّ الْقَيُّوْمُ وَأَتُوْبُ إِلَيْهِ</p>
            <p>Astaghfirullaahal-Adziim, Alladzii Laa Ilaaha Illaa Huwalhayyul-Qayyuum, Wa Atuubu Ilaiih. (Dibaca Sebanya 3 X)</p>
            <p>Artinya : “Saya mohon ampun kepada allahyang maha besar, tidak ada tuhan melainkan dia, yang maha hidup yang terus-menerus mengurus makhluknya, dan saya bertobat kepadanya.”</p>
        </div>
        
        <div class="mb-4">
            <p>2. Membaca Laa Ilaaha Illallah 3 x</p>
            <p>لَااِلَهَ اِلَّا اللهُ وَحْدَهُ لَاشَرِيْكَ لَهُ لَهُ الْمُلْكُ وَلَهُ الْحَمْدُ يُحْيِ وَيُمِيْتُ وَهُوَ عَلَى كُلِّ شَيْئٍ قَدِيْر</p>
            <p>“Laa ilaha illallah wahdahu laa syarika lah, lahul mulku wa lahul hamdu wa huwa ‘ala kulli syai-in qodiir.</p>
            <p>Artinya: Tidak ada yang berhak disembah selain Allah. Dia Yang Maha Tunggal tidak ada sekutu bagi-Nya. Milik-Nya kerajaan dan bagi-Nya segala puji dan Dia Maha Kuasa atas segala sesuatu. Dibaca 3 kali.</p>
        </div>

        <div class="mb-4">
            <p>3. Doa perlindungan siksa neraka 3 x</p>
            <p>اَللَّهُمَّ أَجِرْنِـى مِنَ النَّارِ</p>
            <p>Allohumma ajirnii minanar dibaca 3 x</p>
            <p>اَللَّهُمَّ لاَ مَانِعَ لِمَا أَعْطَيْتَ وَلاَ مُعْطِيَ لِمَا مَنَعْتَ وَلاَ يَنْفَعُ ذَا الْجَدِّ مَنْكَ الْجَدُّ, لاَ إِلَهَ إِلاَّ أَنْتَ</p>
            <p>Allahumma la mani a lima a thaita wa la mu’thiya lima mana’ta wa la yanfa’u dzal jadii minkal jaddu, la ilaha illa anta.</p>
            <p>“Ya Allah tidak ada yang menghalangi bagi apa yang telah Engkau berikan dan tidak kepada orang yang kaya di sisi Engkau segala kekayaanya selain dari kebesaran-Mu ya Rabb. Tidak ada Tuhan yang layak disembah selain Engkau.”</p>
        </div>

        <div class="mb-4">
            <p>4. Doa Keselamatan</p>
            <p>للَّهُمَّ أَنْتَ السَّلاَمُ، وَمِنْكَ السَّلَامُ، وَإِلَيْكَ يَعُوْدُ السَّلَامُ فَحَيِّنَارَبَّنَا بِالسَّلَامِ وَاَدْخِلْنَا الْـجَنَّةَ دَارَ السَّلَامِ تَبَارَكْتَ رَبَّنَا وَتَعَالَيْتَ يَا ذَاالْـجَلَالِ وَاْلإِكْرَام.</p>
            <p>Allahumma Antas-Salaamu Wamingkas-Salaamu Wa Ilaika Ya Uudus-Salaamu Fa Hayyinaa Rabbanaa Bis-Salaami Wa Adkhilna-Jannata Daaros-Salaami Tabaarokta Robbanaa Wa Ta Aalaita Ya Dzal-Jalaali Wal Ikroom”</p>
            <p>Artinya : “Ya allah, engkau adalah zat yang mempunyaikesejahtraan dan daripadamulah kesejahtraan itu da kepadamulah akan kembali lagi segala kesejahtraan itu, maka hidupkanlah kami ya allah dengan sejahtera. dan masukanlah kami kedalam surga kampung kesejahtraan, engkaulah yang kuasa memberi berkah yang banyak dan engkaulah yang maha tinggi, wahai zat yang memiliki ke agungan dan kemulyaan.”</p>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
</script>
@endpush