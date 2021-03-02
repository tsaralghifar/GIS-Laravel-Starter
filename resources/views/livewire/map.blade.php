<div>
    <div id='map' style='width: 400px; height: 300px;'></div>
</div>

@push('script')
    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoiYWxndHNhciIsImEiOiJja2xxajByOTUwMWJuMm9wOWN6aTB6NzNzIn0.nzwakMRehQTTenByElz4qw';
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11'
        });
    </script>
@endpush