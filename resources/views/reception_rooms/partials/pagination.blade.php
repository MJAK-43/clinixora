@props(['paginator', 'label', 'perPage'])

@include('care_rooms.partials.pagination', [
    'paginator' => $paginator,
    'label' => $label,
    'perPage' => $perPage,
    'indexRoute' => 'reception_rooms.index',
    'pageParam' => 'reception_rooms_page',
])
