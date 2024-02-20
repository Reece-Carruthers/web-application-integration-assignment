import {useEffect, useState} from "react"

/**
 * A custom generic hook to interface with the API allows for a URL to be passed in and returns the data, loading state and error state
 * If the first call fails it will try to fetch the data equal to the number of retries, defaulted to 1 attempt more
 * @author Reece Carruthers (W19011575)
 *
 * @generated - had ChatGPT help me with the correct way of creating a custom hook and initialising a URL with query params
 */
export const useFetchData = (url, params = {}, retries = 1) => {
    const [data, setData] = useState(null)
    const [isLoading, setIsLoading] = useState(true)
    const [isError, setIsError] = useState(null)

    useEffect(() => {
        const queryParams = new URLSearchParams()
        for (const key in params) {
            if (params[key] !== undefined) {
                queryParams.append(key, params[key])
            }
        }

        const urlWithParams = new URL(url)
        urlWithParams.search = queryParams.toString()

        const fetchData = (attempt = 2) => {
            fetch(urlWithParams)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Something went wrong: ${response.status} - ${response.statusText}`)
                    }
                    return response.json()
                })
                .then(json => {
                    setData(json)
                    setIsLoading(false)
                })
                .catch((error) => {
                    if (attempt <= retries) {
                        setTimeout(() => fetchData(attempt + 1), 500)
                    } else {
                        setIsError(error)
                        setIsLoading(false)
                    }
                })
        }

        fetchData()
    }, [url, params, retries])

    return { data, isLoading, isError }
}