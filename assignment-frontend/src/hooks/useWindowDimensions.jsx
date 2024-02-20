import { useState, useEffect } from 'react'

/**
 * Gets the current window dimensions on resize
 * @author QoP of StackOverFlow (https://stackoverflow.com/questions/36862334/get-viewport-window-height-in-reactjs) - (https://stackoverflow.com/users/4484822/qop)
 * @returns {{width: number, height: number}}
 */
function getWindowDimensions() {
    const { innerWidth: width, innerHeight: height } = window
    return {
        width,
        height
    }
}

export default function useWindowDimensions() {
    const [windowDimensions, setWindowDimensions] = useState(getWindowDimensions())

    useEffect(() => {
        function handleResize() {
            setWindowDimensions(getWindowDimensions())
        }

        window.addEventListener('resize', handleResize)
        return () => window.removeEventListener('resize', handleResize)
    }, [])

    return windowDimensions
}
