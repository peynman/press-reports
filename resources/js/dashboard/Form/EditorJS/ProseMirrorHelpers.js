import {
    setBlockType
} from 'prosemirror-commands'
import {
    findParentNodeOfType
} from 'prosemirror-utils'

export const updateAttrOfNodeOfTypeCommand = (getNodesCallback, args) => {
    return (state, dispatch, editorView) => {
        if (state.selection.empty) {
            const search = findParentNodeOfType(getNodesCallback(state))(state.selection)
            if (dispatch && search.node) {
                return setBlockType(search.node.type, {
                    ...search.node.attrs,
                    ...args
                })(state, dispatch, editorView);
            }
            return search.node;
        } else {
            console.log(state.selection);
            return true;
        }

        return false;
    }
}
export const isAttrEqInNodeOfType = (getNodesCallback, args) => {
    return (state) => {
        const search = findParentNodeOfType(getNodesCallback(state))(state.selection);
        if (search.node) {
            for (let prop in args) {
                if (search.node.attrs[prop] !== args[prop]) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }
}

export const getAttrsInNodeOfType = (getNodesCallback) => {
    return (state) => {
        const search = findParentNodeOfType(getNodesCallback(state))(state.selection);
        if (search.node) {
            return search.node.attrs;
        }

        return false;
    }
}

export function getRandomString(len) {
    let result = "";
    const characters =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    let charactersLength = characters.length;
    for (let i = 0; i < len; i++) {
        result += characters.charAt(
            Math.floor(Math.random() * charactersLength)
        );
    }
    return result;
}
