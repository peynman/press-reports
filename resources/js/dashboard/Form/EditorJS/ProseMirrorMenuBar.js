import { Plugin } from 'prosemirror-state'

export function menubar(items, component) {
    return new Plugin({
        view(editorView) {
            const editorMenu = new MenuBar(
                items,
                component,
                editorView
            );
            component.editorMenu = editorMenu; // set our host component editorMenu reference
            return editorMenu;
        }
    })
}

export default class MenuBar {
    constructor(items, component, editorView) {
        this.items = items;
        this.component = component;
        this.editorView = editorView;
        this.update();
    }

    onClick(command, args = null) {
        if (!args) {
            command(
                this.editorView.state,
                this.editorView.dispatch,
                this.editorView
            );
        } else {
            command(args)(
                this.editorView.state,
                this.editorView.dispatch,
                this.editorView
            )
        }
        this.editorView.focus();
    }

    isVisible(command) {
        return command(this.editorView.state, null, this.editorView);
    }

    isMarkActive(type) {
        let {
            from,
            $from,
            to,
            empty
        } = this.editorView.state.selection;
        if (empty) {
            return type.isInSet(this.editorView.state.storedMarks || $from.marks())
        }

        return this.editorView.state.doc.rangeHasMark(from, to, type);
    }

    update() {
        this.items.forEach(item => {
            item.isVisible = item.command(
                this.editorView.state,
                null,
                this.editorView
            );
            if (item.isActiveCallback) {
                item.isActive = item.isActiveCallback(this.editorView.state);
            } else if (item.updateValueCallback) {
                item.value = item.updateValueCallback(this.editorView.state);
            } else if (item.mark) {
                item.isActive = this.isMarkActive(item.mark);
            }
        });
    }
}
