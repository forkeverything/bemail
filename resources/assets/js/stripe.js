
export const Stripe = {
    key: null,
    instance: null,
    elements: null
};

export const baseStyle = {
    base: {
        color: '#32325d',
        lineHeight: '18px',
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
            color: '#aab7c4'
        }
    },
    invalid: {
        color: '#fa755a',
        iconColor: '#fa755a'
    }
};

function init() {

    let libraryNotLoadedMessage = 'Stripe V3 lib not loaded!';

    if (Stripe.key === null) {
        Stripe.key = $('meta[name="stripe-key"]').attr('content');
    }

    if(window.Stripe === undefined && Stripe.instance === null) {
        console.error(libraryNotLoadedMessage);
    } else if (Stripe.instance === null) {
        Stripe.instance = window.Stripe(Stripe.key);
    }

    if(! Stripe.instance.elements) {
        console.error(libraryNotLoadedMessage);
    } else if (Stripe.elements === null) {
        Stripe.elements = Stripe.instance.elements();
    }

}

export function createStripe(elementType, options = {}) {
    init();
    options.style = Object.assign(baseStyle, options.style || {});
    return Stripe.elements.create(elementType, options);
}
