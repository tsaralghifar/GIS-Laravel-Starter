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
                    <form 
                        @if ($idEdit)
                            wire:submit.prevent="updateLocation"
                        @else
                            wire:submit.prevent="saveLocation"
                        @endif
                    >
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Longtitude</label>
                                    <input wire:model="long" type="text" class="form-control">
                                    @error('long')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Lattitude</label>
                                    <input wire:model="lat" type="text" class="form-control">
                                    @error('lat')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Title</label>
                            <input wire:model="title" type="text" class="form-control">
                            @error('title')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Desciption</label>
                            <textarea wire:model="description" class="form-control"></textarea>
                            @error('description')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Picture</label>
                            <div class="custom-file">
                                <input wire:model="image" type="file" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                            @error('image')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                            @if ($image)
                                <img src="{{$image->temporaryUrl()}}" class="img-fluid">
                            @endif
                            @if ($imageUrl && !$image)
                                <img src="{{asset('/storage/images/'.$imageUrl)}}" class="img-fluid">
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-dark text-white btn-block">{{$idEdit ? "Save Update" : "Save Location"}}</button>
                            @if ($idEdit)
                                <button wire:click="destroyLocation" type="button" class="btn btn-danger text-white btn-block">Delete</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



@push('scripts')
    <script>
        document.addEventListener('livewire:load', () => {
            const defaultLocation = [114.61739664621774, -3.3398424494228607];
            mapboxgl.accessToken = '{{env('MAP_KEY')}}';
            var map = new mapboxgl.Map({
                container: 'map',
                center: defaultLocation,
                zoom: 11.15,
                style: 'mapbox://styles/mapbox/streets-v11'
            });

            

            const loadLocation = (geoJson) => {
                geoJson.features.forEach((location) => {
                    const {geometry, properties} = location
                    const {iconSize, locationId, title, image, description} = properties

                    let markerElement = document.createElement('div')
                    markerElement.className = 'marker' + locationId
                    markerElement.id = locationId
                    markerElement.style.backgroundImage = 'url(https://docs.mapbox.com/help/demos/custom-markers-gl-js/mapbox-icon.png)'
                    markerElement.style.backgroundSize = 'cover'
                    markerElement.style.width = '50px'
                    markerElement.style.height = '50px'

                    const imageStorage = '{{asset("/storage/images")}}' + '/' + image

                    const content = `
                                    <div style="overflow-y, auto;max-height:400px,width:100%">
                                    <table class="table table-sm mt-2">
                                        <tbody>
                                            <tr>
                                                <td>Title</td>
                                                <td>${title}</td>
                                            </tr>
                                            <tr>
                                                <td>Picture</td>
                                                <td><img src="${imageStorage}" loading="lazy" class="img-fluid"></td>
                                            </tr>
                                            <tr>
                                                <td>Desciption</td>
                                                <td>${description}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                           ` 
                           
                           markerElement.addEventListener('click', (e) => {
                               const locationId = e.toElement.id
                               @this.findLocationById(locationId)
                           })

                    const popUp = new mapboxgl.Popup({
                        offset:25
                    }).setHTML(content).setMaxWidth("400px")

                    new mapboxgl.Marker(markerElement)
                    .setLngLat(geometry.coordinates)
                    .setPopup(popUp)
                    .addTo(map)
                })
            }

            loadLocation({!! $geoJson !!})

            window.addEventListener('locationAdded', (e) => {
                loadLocation(JSON.parse(e.detail))
            })

            window.addEventListener('updateLocation', (e) => {
                loadLocation(JSON.parse(e.detail))
                $('.mapboxgl-popup').remove()
            })

            window.addEventListener('destroyLocation', (e) => {
                $('.marker' + e.detail).remove()
                $('.mapboxgl-popup').remove()
            })

            map.addControl(new mapboxgl.NavigationControl())

            map.on('click', (e) =>  {
                const longtitude = e.lngLat.lng
                const lattitude = e.lngLat.lat

                @this.long = longtitude
                @this.lat = lattitude
            })
        })
    </script>
@endpush

{{-- <script>
        document.addEventListener('livewire:load',  ()  => {
            const defaultLocation = [114.61739664621774, -3.3398424494228607];
            mapboxgl.accessToken = '{{env('MAP_KEY')}}';
            let map = new mapboxgl.Map({
                container: "map",
                center: defaultLocation,
                zoom: 11.15,
                style: "mapbox://styles/mapbox/streets-v11"
            });
            //light-v10, outdoors-v11, satellite-v9, streets-v11, dark-v10
            // const style = "streets-v11"
            // map.setStyle(`mapbox://styles/mapbox/${style}`);

            map.addControl(new mapboxgl.NavigationControl());
        
            map.on('click', (e) =>  {
            const longtitude = e.lngLat.lng
            const lattitude = e.lngLat.lat
        
            console.log({longtitude, lattitude});
            })
        
        })
        </script> --}}
