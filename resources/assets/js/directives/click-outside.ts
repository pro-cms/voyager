export default {
    mounted(el: Element, binding: any) {
        var first = false;

        document.documentElement.addEventListener('mousedown', (e) => {
            var target = <HTMLElement>e.target;
            var inside = false;

            do {
                if (target == el) {
                    inside = true;
                }
            } while (target = <HTMLElement>target?.parentNode);

            if (!inside && first) {
                binding.value();
            }

            first = true;
        }, true);
    }
};