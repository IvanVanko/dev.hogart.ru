ymaps.ready(init);

function init () {

    var myMap = new ymaps.Map("map", {
            //center: [55.76, 37.64],
            center: [mapCenter[0], mapCenter[1]],
            zoom: 16
        }),


    // Создаем геообъект с типом геометрии "Точка".
        myGeoObject = new ymaps.GeoObject({
            // Описание геометрии.
            geometry: {
                type: "Point",
                coordinates: [mapCenter[0], mapCenter[1]]
            }});
            // Свойства.
            /*properties: {
                // Контент метки.
                iconContent: 'Я тащусь',
                hintContent: 'Ну давай уже тащи'
            }*/
        /*}, {
            // Опции.
            // Иконка метки будет растягиваться под размер ее содержимого.
            preset: 'islands#blackStretchyIcon',
            // Метку можно перемещать.
            draggable: true
        });*/

    myMap.geoObjects
        .add(myGeoObject)
        .add(new ymaps.Placemark([mapCenter[0], mapCenter[1]], {
            balloonContent: semName
        }/*, {
            preset: 'islands#icon',
            iconColor: '#0095b6'
        }*/));
    myMap.behaviors.disable('scrollZoom');
}
