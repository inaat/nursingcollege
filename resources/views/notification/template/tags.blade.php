<div id="tags">
    <h3>Tags Information</h3>

  @foreach($tags as $key => $tag)
  @foreach($tag as $key => $value)

        <a data-value="{{ $value }}" class="btn btn-gradient-light btn_tag mt-2">{{ $value }} </a>    

  @endforeach
  @endforeach


  

</div>
