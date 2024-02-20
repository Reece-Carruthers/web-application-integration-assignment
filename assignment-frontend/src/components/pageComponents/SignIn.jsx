import {useContext, useEffect, useRef, useState} from 'react'
import UserContext from "../../helpers/userContext.jsx";
import toast, {Toaster} from 'react-hot-toast';


/**
 * Sign in component
 * @returns {JSX.Element}
 * @author Reece Carruthers (W19011575) and module materials
 */
export default function SignIn() {
    const [isError, setIsError] = useState(false);

    const user = useContext(UserContext)

    useEffect(() => {
        const token = localStorage.getItem('token')
        if (token !== null) {
            user.setSignedIn(true)
        }
    }, [])

    useEffect(() => {
        if(user.signedIn === false) {
            localStorage.removeItem('token')
            username.value = ''
            password.value = ''
        }
    }, [user.signedIn])

    const username = useRef(null);
    const password = useRef(null);

    const handleSignIn = () => {

        const credentials = username.current.value + ':' + password.current.value;

        fetch('https://w19011575.nuwebspace.co.uk/assignment/api/token',
            {
                method: 'GET',
                headers: new Headers({"Authorization": "Basic " + btoa(credentials)})
            }
        ).then(res => {
            if (res.status === 200) {
                setIsError(false)
                user.setSignedIn(true)
            } else if (res.status === 401) {
                setIsError(true)
            } else {
                throw new Error('Failed to sign in')
            }
            return res.json()
        }).then(data => {
            if (data.token) {
                localStorage.setItem('token', data.token)
            }
        }).catch(() => toast.error('There was an error signing in'))
    }

    return (
        <>
            <Toaster />
            <div className='bg-slate-800 p-2 text-md text-right'>
                {!user.signedIn && <div className="flex flex-col md:flex-row m-2 p-2 md:justify-end">
                    <input
                        type="text"
                        placeholder='username'
                        ref={username}
                        className={`m-2 p-1 mx-2 rounded-md ${isError ? 'bg-red-100' : 'bg-slate-100'}`}
                    />
                    <input
                        type="password"
                        placeholder='password'
                        ref={password}
                        className={`m-2 p-1 mx-2 rounded-md ${isError ? 'bg-red-100' : 'bg-slate-100'}`}
                    />
                    <input
                        type="submit"
                        value='Sign In'
                        className='m-2 py-1 px-2 mx-2 bg-green-100 hover:bg-green-500 rounded-md'
                        onClick={handleSignIn}
                    />
                </div>
                }
                {user.signedIn && <div>
                    <input
                        type="submit"
                        value='Sign Out'
                        className='py-1 px-2 mx-2 bg-green-100 hover:bg-green-500 rounded-md'
                        onClick={() => user.setSignedIn(false)}
                    />
                </div>
                }
            </div>
        </>
    )
}