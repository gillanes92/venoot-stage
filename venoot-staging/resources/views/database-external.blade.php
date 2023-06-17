<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Venoot - Database {{ $database->name }} </title>

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

        <!-- Font Awesome -->
        <script src="https://kit.fontawesome.com/c3427198ef.js" crossorigin="anonymous"></script>

    </head>
    <body>
        <div class="container-lg mt-4">
            <div class="row">
                <div class="col">
                    <h4>{{ $database->name }}</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellido</th>
                            <th scope="col">Correo</th>

                             @foreach($database->fields as $field)
                                @if(!in_array($field['key'], ['name', 'lastname', 'email']))
                                    <th scope="col">{{ strtoupper($field['name']) }}</th>
                                @endif
                            @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($database->participants as $participant)
                                <tr>
                                    <td>{{ $participant->data['name'] }}</td>
                                    <td>{{ $participant->data['lastname'] }}</td>
                                    <td>{{ $participant->data['email'] }}</td>
                                    @foreach($database->fields as $field)
                                        @if(!in_array($field['key'], ['name', 'lastname', 'email']))
                                            @if (array_key_exists($field['key'], $participant->data))

                                                <!-- PDF -->
                                                @if($field['type'] == 'pdf')
                                                    <td><a href="{{ Storage::url($participant->data[$field['key']]) }}" target="_blank"><i class="fas fa-file-pdf"></i></a></td>
                                                @elseif($field['type'] == 'image')
                                                    <td><a href="{{ Storage::url($participant->data[$field['key']]) }}" target="_blank"><i class="fas fa-image"></i></a></td>
                                                @else
                                                    <td>{{ $participant->data[$field['key']] }}</td>
                                                @endif
                                            @else
                                                <td></td>
                                            @endif
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>

    <!-- Bootstrap + Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
</html>
