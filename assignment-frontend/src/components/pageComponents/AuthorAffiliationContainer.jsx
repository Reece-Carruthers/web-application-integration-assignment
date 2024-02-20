import {useFetchData} from "../../hooks/useFetchData.jsx"
import {useMemo} from "react"

/**
 * A component to fetch and display information about author and affiliation content using a contentID
 * @param id
 * @returns {JSX.Element}
 * @author Reece Carruthers (W19011575)
 */
export default function AuthorAffiliationContainer({contentId}) {

    const authorParams = useMemo(() => ({'contentID': contentId}), [contentId])
    const {
        data: authorAffiliateData,
        isLoading: isAuthorAffiliateLoading,
        isError: isAuthorAffiliateError
    } = useFetchData('https://w19011575.nuwebspace.co.uk/assignment/api/author-affiliation', authorParams)

    return (
        <>
            <div className="bg-orange-100 p-2 rounded-md drop-shadow-md hover:shadow-xl">
                {isAuthorAffiliateLoading && <p>Loading author and affiliation data...</p>}
                {isAuthorAffiliateError && <p>There was an error loading author and affiliation data</p>}
                <div className="flex flex-col 2xl:flex-row overflow-auto text-wrap flex-wrap">
                    {!isAuthorAffiliateError && authorAffiliateData && authorAffiliateData.data && authorAffiliateData.data.map(authorAffiliate => (
                        <div className="2xl:text-xl mb-4 bg-neutral-200 rounded-md drop-shadow-md m-2" key={authorAffiliate.author_id}>
                            {authorAffiliate.author_name && <p aria-label="author name" className="p-1 border-b-2 border-orange-100">{authorAffiliate.author_name}</p>}
                            {authorAffiliate.affiliation_country && <p aria-label="affiliation country" className="p-1 border-b-2 border-orange-100">{authorAffiliate.affiliation_country}</p>}
                            {authorAffiliate.affiliation_city && <p aria-label="affiliation city" className="p-1 border-b-2 border-orange-100">{authorAffiliate.affiliation_city}</p>}
                            {authorAffiliate.affiliation_institution && <p aria-label="affiliation institution" className="p-1">{authorAffiliate.affiliation_institution}</p>}
                        </div>
                    ))}
                </div>
            </div>
        </>
    )
}
