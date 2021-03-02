<div class="container-fluid">
    <div class="row bg-dark">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">
                    GIS MAP
                </div>
                <div class="card-body">
                    <div wire:ignore id='map' style='width: 100%; height: 75vh;'></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    Form
                </div>
                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    {{-- <script>
        document.addEventListener('liveware:load', () => {
            const defaultLocation = [114.61739664621774, -3.3398424494228607];
            mapboxgl.accessToken = '{{env('MAP_KEY')}}';
            var map = new mapboxgl.Map({
                container: 'map',
                center: defaultLocation,
                zoom: 11.15,
                style: 'mapbox://styles/mapbox/streets-v11'
            });

            map.addControl(new mapboxgl.NavigationControl())

            map.on('click', (e) =>  {
                const longtitude = e.lngLat.lng
                const lattitude = e.lngLat.lat

                console.log({longtitude, lattitude});
            })
        })
    </script> --}}
    <script>
        document.addEventListener('livewire:load',  ()  => {
            const defaultLocation = [114.61739664621774, -3.3398424494228607];
            mapboxgl.accessToken = '{{env('MAP_KEY')}}';
            let map = new mapboxgl.Map({
                container: "map",
                center: defaultLocation,
                zoom: 11.15,
                style: "mapbox://styles/mapbox/streets-v11"
            });
            map.addControl(new mapboxgl.NavigationControl());
            
            //light-v10, outdoors-v11, satellite-v9, streets-v11, dark-v10
            const style = "streets-v11"
            map.setStyle(`mapbox://styles/mapbox/${style}`);
        
            map.on('click', (e) =>  {
            const longtitude = e.lngLat.lng
            const lattitude = e.lngLat.lat
        
            console.log({longtitude, lattitude});
            })
        
        })
        </script>
@endpush
