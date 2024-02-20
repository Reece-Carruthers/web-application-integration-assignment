import {Link, NavLink} from 'react-router-dom'
import useWindowDimensions from "../../hooks/useWindowDimensions.jsx";
import {useState} from "react";

/**
 * Main menu
 *
 * This will be the main navigation component in
 * the app, with links to all main pages
 *
 * @author Reece Carruthers
 */
export default function NavigationMenu() {
    const renderNavigationLink = (to, label) => (
        <NavLink
            to={to}
            className={({ isActive }) =>
                `w-11/12 m-2 rounded-xl shadow-lg text-center sm:text-base md:text-lg lg:text-xl 2xl:text-2xl ${
                    isActive ? 'bg-orange-400 font-bold' : 'bg-orange-200 hover:bg-orange-300 active:bg-orange-400'
                }`
            }
        >
            <li>{label}</li>
        </NavLink>
    )

    return (
        <div className="flex flex-row shadow bg-neutral-100">
            <h1 className={"w-1/4 text-4xl 2xl:text-6xl p-1 text-center text-black"}>CHI 2023</h1>
                <ul className="w-3/4 m-1 flex flex-col sm:flex-row justify-center items-center">
                    {renderNavigationLink("/", "Home")}
                    {renderNavigationLink("/countries", "Countries")}
                    {renderNavigationLink("/content", "Content")}
                </ul>
        </div>
    )
}