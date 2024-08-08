( function( blocks, element ) {
    var el = element.createElement;

    blocks.registerBlockType( 'lgbtq-connect/custom-block', {
        title: 'LGBTQ+ Connect',
        icon: 'location',
        category: 'widgets',
        edit: function( props ) {
            return el(
                'div',
                { className: `${props.className} gradient-border`},
                '(Local onde o mapa LGBTQ+ Connect será exibido)'
            );
        },
    } );
} )( window.wp.blocks, window.wp.element );
