
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Pinyon%20Script:wght@900&family=Pinyon%20Script&display=swap');

        #certificate {
             height : {{ $layout['height'] }}px;
            width : {{ $layout['width'] }}px;
            border: 2px solid #000;
            position: relative;
            background: #fff;
            margin: 20px auto;
            overflow: hidden;
        }
        
        .draggable {
            position: absolute;
            cursor: move;
            user-select: none;
        }
        
        .element-controls {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
          
            display: none;
            position: absolute;
            z-index: 1000;
        }
        
        .draggable:hover .element-controls {
            display: block;
        }
        
    #background-image {
    position: absolute;
    height: 100%; /* Stretch to fill container's height */
    width: 100%;  /* Stretch to fill container's width */
 object-fit: fill;    z-index: 0;
}
        
      
        
        .img {
            max-width: 100%;
            max-height: 100%;
        }
        
   
        .size-control {
            width: 70px;
            margin: 0 5px;
        }

        .control-label {
            font-size: 12px;
            margin-right: 5px;
        }

        
    </style>
    
    @if ($certificateTemplate->user_image_shape == 'Round')
        <style>
            .item_user_image {
                border-radius: 50%;
            border: 1px solid #27282AFF;
            }
        </style>
    @else
        <style>
            .item_user_image {
                border-radius: 6%;
                            border: 1px solid #27282AFF;

            }
        </style>
    @endif