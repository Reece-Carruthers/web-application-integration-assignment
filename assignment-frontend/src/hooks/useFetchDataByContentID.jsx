import {useEffect, useState} from "react"

/**
 * Fetches additional data by grabbing the content_id from the data passed in and using it to fetch additional data from the API
 * @author Reece Carruthers (W19011575)
 */
export const useFetchDataByContentID = (url, data) => {
    const [additionalData, setAdditionalData] = useState(null)
    const [isAdditionalLoading, setIsAdditionalLoading] = useState(true)
    const [isAdditionalError, setIsAdditionalError] = useState(null)

    useEffect(() => {
        if (data && data.data && data.data.length > 0) {
            const id = data.data[0].content_id

            const queryParams = new URLSearchParams()
            queryParams.append('contentID', id)

            const urlWithParams = new URL(url)
            urlWithParams.search = queryParams.toString()

            fetch(urlWithParams)
                .then(response => {
                    if(response.status !== 200) {
                        throw new Error(`Something went wrong: ${response.status} - ${response.statusText}`)
                    }
                    return response.json()
                })
                .then(data => {
                    setAdditionalData(data)
                    setIsAdditionalLoading(false)
                })
                .catch(error => {
                    setIsAdditionalError(error)
                    setIsAdditionalLoading(false)
                });

        }
    }, [url, data])

    return {additionalData, isAdditionalLoading, isAdditionalError}
}