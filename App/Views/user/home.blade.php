<html>
<head>
    <title>{{$title}}</title>
</head>
<body>

    @foreach($items as $key => $value)
        {{ $value->id  }} : {{ $value->name  }}
        <br>

    @endforeach

    <div>
      <?php echo \Src\Database\Database::get_links(pages_num: $pageCount); ?>
    </div>





</body>
</html>