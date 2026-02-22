/**
 * QRCode & html2canvas loader
 * Replaces CDN-loaded qrcodejs and html2canvas libraries.
 * These are exposed globally so inline <script> tags in Blade views can use them.
 */
import QRCode from 'qrcode';
import html2canvas from 'html2canvas';

// Shim: QRCodeJS API compatibility layer
// The old qrcodejs library uses `new QRCode(element, options)` syntax.
// The `qrcode` npm package has a different API (canvas/toDataURL based).
// This shim provides the same constructor interface.
class QRCodeShim {
    static CorrectLevel = { L: 'L', M: 'M', Q: 'Q', H: 'H' };

    constructor(element, options) {
        if (typeof options === 'string') {
            options = { text: options };
        }

        this.element = typeof element === 'string' ? document.getElementById(element) : element;
        this.options = options;
        this._render();
    }

    _render() {
        if (!this.element || !this.options.text) return;

        const width = this.options.width || 256;
        const height = this.options.height || 256;
        const colorDark = this.options.colorDark || '#000000';
        const colorLight = this.options.colorLight || '#ffffff';
        const correctLevel = this.options.correctLevel || 'H';

        const canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;

        QRCode.toCanvas(canvas, this.options.text, {
            width: width,
            margin: 0,
            color: {
                dark: colorDark,
                light: colorLight,
            },
            errorCorrectionLevel: correctLevel,
        }, (error) => {
            if (error) {
                console.error('QRCode generation error:', error);
                return;
            }
            this.element.innerHTML = '';
            this.element.appendChild(canvas);
        });
    }

    clear() {
        if (this.element) this.element.innerHTML = '';
    }

    makeCode(text) {
        this.options.text = text;
        this._render();
    }
}

// Expose globally for inline scripts in Blade views
window.QRCode = QRCodeShim;
window.html2canvas = html2canvas;
