
const { registerBlockType } = wp.blocks;
const { TextControl } = wp.components;

registerBlockType( 'theme/favorite-movie-quote', {
    title: 'Favorite Movie Quote',
    icon: 'format-quote',
    category: 'common',
    attributes: {
        quote: {
            type: 'string',
            source: 'text',
            selector: 'blockquote',
        },
    },
    edit: ( props ) => {
        const { attributes: { quote }, setAttributes } = props;

        const onChangeQuote = ( value ) => {
            setAttributes( { quote: value } );
        };

        return wp.element.createElement(
            'div',
            { className: props.className },
            wp.element.createElement(
                'blockquote',
                null,
                quote
            ),
            wp.element.createElement(
                TextControl,
                {
                    label: 'Enter your favorite movie quote',
                    value: quote,
                    onChange: onChangeQuote
                }
            )
        );
    },
    save: ( props ) => {
        const { attributes: { quote } } = props;

        return wp.element.createElement(
            'blockquote',
            null,
            quote
        );
    },
} );
